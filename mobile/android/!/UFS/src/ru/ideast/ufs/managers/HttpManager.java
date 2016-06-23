package ru.ideast.ufs.managers;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.conn.params.ConnManagerParams;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.tsccm.ThreadSafeClientConnManager;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;

import ru.ideast.ufs.exceptions.CorruptedDataException;
import ru.ideast.ufs.exceptions.NoNetworkException;
import ru.ideast.ufs.utils.ToolBox;
import android.content.Context;
import android.os.Environment;

import com.fasterxml.jackson.core.JsonGenerationException;
import com.fasterxml.jackson.databind.DeserializationFeature;
import com.fasterxml.jackson.databind.JsonMappingException;
import com.fasterxml.jackson.databind.MapperFeature;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.jsontype.*;
import com.fasterxml.jackson.annotation.PropertyAccessor;
import com.fasterxml.jackson.annotation.JsonAutoDetect.*;
import com.fasterxml.jackson.core.json.*;

public enum HttpManager {

	INSTANCE;
	
	private static final int NETWORK_HTTP_TIMEOUT_MS = 20000;
	
	private Context context;
	private DefaultHttpClient httpClient;
	private ObjectMapper mapper;
	
	private HttpManager() {
		mapper = new ObjectMapper();
		mapper.configure(DeserializationFeature.FAIL_ON_UNKNOWN_PROPERTIES, false);
		mapper.configure(MapperFeature.USE_ANNOTATIONS, false);
		/* tsv */
		//mapper.configure(DeserializationFeature.FAIL_ON_IGNORED_PROPERTIES, false);
		//mapper.configure(MapperFeature.AUTO_DETECT_FIELDS, false);
		/* tsv */
		
		BasicHttpParams params = new BasicHttpParams();
		ConnManagerParams.setTimeout(params, NETWORK_HTTP_TIMEOUT_MS);
		HttpConnectionParams.setConnectionTimeout(params, NETWORK_HTTP_TIMEOUT_MS);
		HttpConnectionParams.setSoTimeout(params, NETWORK_HTTP_TIMEOUT_MS);		
		
		SchemeRegistry scheme = new SchemeRegistry();
		scheme.register(new Scheme("http", PlainSocketFactory.getSocketFactory(), 80));
		scheme.register(new Scheme("https", TrustAllSSLSocketFactory.createTrustAllSSLSocketFactory(), 443));

		ThreadSafeClientConnManager conMngr = new ThreadSafeClientConnManager(params, scheme);
		
		httpClient = new DefaultHttpClient(conMngr, params);
	}
	
	public void init(Context context) {
		this.context = context.getApplicationContext();
	}
	
	public <T> T getData(String url, Class<T> clazz) throws NoNetworkException, CorruptedDataException {
		InputStream inputStream = null;
		try {
			inputStream = openStream(url);
			return mapper.readValue(inputStream, clazz);
		/* tsv */	
		} catch (JsonGenerationException e) {
			throw new CorruptedDataException();
		} catch (JsonMappingException e) {
			throw new CorruptedDataException();	
		/* tsv */	
		} catch (NoNetworkException e) {
			throw e;
		} catch (CorruptedDataException e) {
			throw e;
		} catch (Exception e) {
			throw new CorruptedDataException();
		} finally {
			ToolBox.quietlyClose(inputStream);
		}		
	}
	
	public <T> List<T> getListData(String url, Class<T> clazz) throws NoNetworkException, CorruptedDataException {
		InputStream inputStream = null;
		try {
			inputStream = openStream(url);
			return mapper.readValue(inputStream, mapper.getTypeFactory().constructCollectionType(List.class, clazz));
		} catch (NoNetworkException e) {
			throw e;
		} catch (CorruptedDataException e) {
			throw e;
		} catch (Exception e) {
			throw new CorruptedDataException();
		} finally {
			ToolBox.quietlyClose(inputStream);
		}		
	}
	
	public File getFile(String url, String fileName) throws IOException, NoNetworkException, CorruptedDataException {
		FileOutputStream fos = null;
		InputStream is = null;
		try {
			File file = new File(getDiskCacheDir(), fileName);
			file.createNewFile();
			
			fos = new FileOutputStream(file);
			is = openStream(url);
			
			byte[] buffer = new byte[1024];
			int len = 0;
			while ((len = is.read(buffer)) > 0) {
				fos.write(buffer, 0, len);
			}
			
			return file;
		} catch (IOException e) {
			throw e;
		} catch (NoNetworkException e) {
			throw e;
		} catch (CorruptedDataException e) {
			throw e;
		} finally {
			ToolBox.quietlyClose(is);
			ToolBox.quietlyClose(fos);
		}
	}
	
	public File getDiskCacheDir() {
		File cacheDir;
		if (Environment.getExternalStorageState().equals(Environment.MEDIA_MOUNTED))
			cacheDir = new File(Environment.getExternalStorageDirectory() + "/Android/data/" + context.getPackageName() + "/pdf");
		else
			cacheDir = context.getCacheDir();
		
		cacheDir.mkdirs();
		return cacheDir;
	}
	
	private InputStream openStream(String url) throws CorruptedDataException, NoNetworkException {
		HttpResponse httpResponse = getHttpResponse(url);
		
		HttpEntity httpEntity = httpResponse.getEntity();
		if(httpEntity == null) {
			throw new CorruptedDataException();
		}
		
		InputStream result;
		try {
			result = httpEntity.getContent();
		} catch (Exception e) {
			throw new NoNetworkException();
		}

		return result;
	}
	
	private HttpResponse getHttpResponse(String url) throws CorruptedDataException, NoNetworkException {
		HttpUriRequest request;
		try {
			request = new HttpGet(url);
			request.addHeader("Accept", "application/json");
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
		
		HttpResponse httpResponse;
		try {
			httpResponse = httpClient.execute(request);
		} catch (Exception e) {
			throw new NoNetworkException();
		}
		
		final int statusCode = httpResponse.getStatusLine().getStatusCode();
		if(statusCode >= 502 && statusCode <= 505) {
			throw new NoNetworkException();
		}
		if (statusCode != HttpStatus.SC_OK) {
			throw new CorruptedDataException();
		}
		
		return httpResponse;
	}
}

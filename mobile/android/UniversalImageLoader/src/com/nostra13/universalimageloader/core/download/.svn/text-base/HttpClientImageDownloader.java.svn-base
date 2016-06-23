/*******************************************************************************
 * Copyright 2011-2013 Sergey Tarasevich
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *******************************************************************************/
package com.nostra13.universalimageloader.core.download;

import java.io.IOException;
import java.io.InputStream;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.conn.params.ConnManagerParams;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.entity.BufferedHttpEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.tsccm.ThreadSafeClientConnManager;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;

import android.content.Context;

/**
 * Implementation of ImageDownloader which uses {@link HttpClient} for image stream retrieving.
 * 
 * @author Sergey Tarasevich (nostra13[at]gmail[dot]com)
 * @since 1.4.1
 */
public class HttpClientImageDownloader extends BaseImageDownloader {

	private HttpClient httpClient;

	public HttpClientImageDownloader(Context context, HttpClient httpClient) {
		super(context);
		this.httpClient = httpClient;
	}
	
	public HttpClientImageDownloader(Context context) {
		super(context);
		this.httpClient = getHttpClient();
	}
	
	private HttpClient getHttpClient() {
		BasicHttpParams params = new BasicHttpParams();
		ConnManagerParams.setTimeout(params, DEFAULT_HTTP_CONNECT_TIMEOUT);
		HttpConnectionParams.setConnectionTimeout(params, DEFAULT_HTTP_CONNECT_TIMEOUT);
		HttpConnectionParams.setSoTimeout(params, DEFAULT_HTTP_CONNECT_TIMEOUT);		
		
		SchemeRegistry scheme = new SchemeRegistry();
		scheme.register(new org.apache.http.conn.scheme.Scheme("http", PlainSocketFactory.getSocketFactory(), 80));
		scheme.register(new org.apache.http.conn.scheme.Scheme("https", TrustAllSSLSocketFactory.createTrustAllSSLSocketFactory(), 443));

		ThreadSafeClientConnManager conMngr = new ThreadSafeClientConnManager(params, scheme);
		
		return new DefaultHttpClient(conMngr, params);
	}

	@Override
	protected InputStream getStreamFromNetwork(String imageUri, Object extra) throws IOException {
		HttpGet httpRequest = new HttpGet(imageUri);
		HttpResponse response = httpClient.execute(httpRequest);
		HttpEntity entity = response.getEntity();
		BufferedHttpEntity bufHttpEntity = new BufferedHttpEntity(entity);
		return bufHttpEntity.getContent();
	}
}

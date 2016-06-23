package ru.ideast.ufs.managers;

import android.os.Build;
import android.util.Log;

import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;
import java.security.KeyManagementException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;

import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

import org.apache.http.conn.ssl.SSLSocketFactory;

public class TrustAllSSLSocketFactory extends SSLSocketFactory {
	
	SSLContext sslContext = SSLContext.getInstance("TLS");
	/* tsv */TrustManager[] trustManager = null;

	public TrustAllSSLSocketFactory(KeyStore truststore) throws NoSuchAlgorithmException, KeyManagementException, KeyStoreException, UnrecoverableKeyException {
		super(truststore);

		TrustManager tm = new X509TrustManager() {
			public void checkClientTrusted (X509Certificate[] chain, String authType) throws CertificateException {}

			//public void checkServerTrusted (X509Certificate[] chain, String authType) throws CertificateException {}
			/* tsv */
			public void checkServerTrusted(java.security.cert.X509Certificate[] certs, String authType) {
				
				if (Build.VERSION.SDK_INT <= 15) {
	                for (X509Certificate cert : certs) {
	                    if (cert != null && cert.getCriticalExtensionOIDs() != null)
	                        cert.getCriticalExtensionOIDs().remove("2.5.29.15");
	                }
				}
            }
			/* tsv */

			/*public X509Certificate[] getAcceptedIssuers () {
				return null;
			}*/
			
			/* tsv */
			public X509Certificate[] getAcceptedIssuers () {
			  return new X509Certificate[0];
		    }
			/* tsv */

		};

		//sslContext.init(null, new TrustManager[] { tm }, null);
		/* tsv */
		trustManager = new TrustManager[] { tm };
		sslContext.init(null, trustManager, null);
		/* tsv */
	}

	@Override
	public Socket createSocket (Socket socket, String host, int port, boolean autoClose) throws IOException, UnknownHostException {
		return sslContext.getSocketFactory().createSocket(socket, host, port, autoClose);
	}

	@Override
	public Socket createSocket () throws IOException {
		return sslContext.getSocketFactory().createSocket();
	}
	
	public static SSLSocketFactory createTrustAllSSLSocketFactory () {
		TrustAllSSLSocketFactory sf = null;
		
		try {
			KeyStore trustStore = KeyStore.getInstance(KeyStore.getDefaultType());
			trustStore.load(null, null);

			sf = new TrustAllSSLSocketFactory(trustStore);
			sf.setHostnameVerifier(SSLSocketFactory.ALLOW_ALL_HOSTNAME_VERIFIER);
			
		} catch (Exception e) {
			Log.e("HTTPS error", "Error during HTTPS TrustManager initialization");
		}

		return sf;
	}
	
	/* tsv */
	public SSLContext getSSLContext() {
		return sslContext;
	}
	
	public TrustManager[] getTrustManagers() {
		return trustManager;
	}
	/* tsv */
}

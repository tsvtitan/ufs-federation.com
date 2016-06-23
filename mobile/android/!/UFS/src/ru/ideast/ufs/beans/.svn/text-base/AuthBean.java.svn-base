package ru.ideast.ufs.beans;

import ru.ideast.ufs.beans.AuthBean.Result;

public class AuthBean extends BaseBean<Result> {

	public static class Result {
		
		private String token;
		private long expired;
		
		public String getToken() {
			return token;
		}
		
		public void setToken(String token) {
			this.token = token;
		}

		public long getExpired() {
			return expired;
		}

		public void setExpired(String expired) {
			this.expired = Long.valueOf(expired) * 1000;
		}
	}
}

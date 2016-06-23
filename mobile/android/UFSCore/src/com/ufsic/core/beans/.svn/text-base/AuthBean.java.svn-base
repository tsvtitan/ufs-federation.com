package com.ufsic.core.beans;

import com.ufsic.core.beans.AuthBean.Result;

public class AuthBean extends BaseBean<Result> {

	public static class Result {
		
		private String token;
		private long expired;
		/* tsv */
		private String email;
		private String phone;
		private String categoryId;
		private String categoryDelay;
		/* tsv */
		
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
		
		/* tsv */
		public String getEmail() {
			return email;
		}
		
		public void setEmail(String email) {
			this.email = email;
		}

		public String getPhone() {
			return phone;
		}
		
		public void setPhone(String phone) {
			this.phone = phone;
		}
		
		public String getCategoryId() {
			return categoryId;
		}
		
		public void setCategoryId(String categoryId) {
			this.categoryId = categoryId;
		}
		
		public String getCategoryDelay() {
			return categoryDelay;
		}
		
		public void setCategoryDelay(String categoryDelay) {
			this.categoryDelay = categoryDelay;
		}
		/* tsv */
	}
}

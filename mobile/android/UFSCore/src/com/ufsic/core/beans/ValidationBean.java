package com.ufsic.core.beans;

/* tsv */

import com.ufsic.core.beans.ValidationBean.Result;

public class ValidationBean extends BaseBean<Result> {

	public static class Result {
		
		private String code;
		
		public String getCode() {
			return code;
		}
		
		public void setCode(String code) {
			this.code = code;
		}

	}
}

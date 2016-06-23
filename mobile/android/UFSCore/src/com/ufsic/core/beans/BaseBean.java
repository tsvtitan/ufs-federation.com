package com.ufsic.core.beans;

public abstract class BaseBean<T> {
	
	private T result;
	private ErrorBean error;
	
	public T getResult() {
		return result;
	}
	
	public void setResult(T result) {
		this.result = result;
	}

	public ErrorBean getError() {
		return error;
	}

	public void setError(ErrorBean error) {
		this.error = error;
	}
}

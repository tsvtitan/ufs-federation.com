package com.ufsic.core.beans;

public abstract class BaseGroupPreviewBean {
	
	private int parentId = -1;

	public int getParentId() {
		return parentId;
	}

	public void setParentId(int parentId) {
		this.parentId = parentId;
	}
	
	public abstract String getName();
	public abstract int getItemsCount();
	public abstract int getItemsActualCount();
	public abstract int getActual();
	public abstract String getDateStr();
}

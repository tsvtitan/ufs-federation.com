package com.polites.android;

import android.view.GestureDetector.SimpleOnGestureListener;
import android.view.MotionEvent;
import android.view.View.OnClickListener;

public class DoubleTapListener extends SimpleOnGestureListener {

	private OnClickListener onSingleTapListener;
	private GestureImageView image;
	
	public DoubleTapListener(GestureImageView image) {
		super();
		this.image = image;
	}

	@Override
	public boolean onDoubleTap(MotionEvent e) {
		return true;
	}

	public void setOnSingleTapListener(OnClickListener listener){
		this.onSingleTapListener = listener;
	}
	
	@Override
	public boolean onSingleTapConfirmed(MotionEvent e) {
		if(onSingleTapListener != null)
			onSingleTapListener.onClick(image);
		return super.onSingleTapConfirmed(e);
	}

}

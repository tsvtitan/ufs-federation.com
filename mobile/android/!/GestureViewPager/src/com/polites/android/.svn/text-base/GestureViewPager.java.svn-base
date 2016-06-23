package com.polites.android;

import java.util.HashSet;

import android.content.Context;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.util.AttributeSet;
import android.view.View;

public class GestureViewPager extends ViewPager {

	private OnPageChangeListener userListener;
	private HashSet<OnPageChangeListener> listeners;
	private OnPageChangeListener listener = new OnPageChangeListener() {
		
		private int currentPosition = -1;
		
		@Override
		public void onPageSelected(int position) {
			currentPosition = position;
			for(OnPageChangeListener listener: listeners)
				listener.onPageSelected(position);
		}
		
		@Override
		public void onPageScrolled(int arg0, float arg1, int arg2) {
			for(OnPageChangeListener listener: listeners)
				listener.onPageScrolled(arg0, arg1, arg2);
		}
		@Override
		public void onPageScrollStateChanged(int state) {
			PagerAdapter adapter = getAdapter();
			if(adapter instanceof GesturePagerAdapter && state == ViewPager.SCROLL_STATE_IDLE){
				int count = adapter.getCount();
				if(count <= 1)
					return;
				GesturePagerAdapter gestureAdapter = (GesturePagerAdapter) adapter;
				if(currentPosition == 0)
					toNormalScaleIfNotNull(gestureAdapter.getImage(currentPosition + 1));
				else if(currentPosition == count - 1)
					toNormalScaleIfNotNull(gestureAdapter.getImage(currentPosition - 1));
				else{
					toNormalScaleIfNotNull(gestureAdapter.getImage(currentPosition + 1));
					toNormalScaleIfNotNull(gestureAdapter.getImage(currentPosition - 1));
				}
			}
			for(OnPageChangeListener listener: listeners)
				listener.onPageScrollStateChanged(state);
		}
	};
	
	private void toNormalScaleIfNotNull(GestureImageView image){
		if(image != null)
			image.toNormalScale();
	}
	
	public GestureViewPager(Context context, AttributeSet attrs) {
		super(context, attrs);
		init();
	}

	public GestureViewPager(Context context) {
		super(context);
		init();
	}
	
	private void init(){
		super.setOnPageChangeListener(listener);
		listeners = new HashSet<ViewPager.OnPageChangeListener>(2);
	}
	
	@Override
	protected boolean canScroll(View v, boolean checkV, int dx, int x, int y) {
		if(v instanceof GestureImageView){
			GestureImageView img = (GestureImageView)v;
			if(!img.isScaled())
				return super.canScroll(v, checkV, dx, x, y);
			if(dx < 0)
				return !img.isRightScroll();
			else
				return !img.isLeftScroll();
		}
		else
			return super.canScroll(v, checkV, dx, x, y);
	}
	
	@Override
	public void setOnPageChangeListener(OnPageChangeListener listener) {
		if(userListener != null)
			removeOnPageChangeListener(userListener);
		
		if(listener != null)
			addOnPageChangeListener(listener);
		
		userListener = listener;
	}
	
	public void addOnPageChangeListener(OnPageChangeListener listener){
		if(listener == null)
			throw new IllegalArgumentException("Добавляемый listener не должен быть равен null");
		listeners.add(listener);
	}
	
	public void removeOnPageChangeListener(OnPageChangeListener listener){
		if(listener == null)
			throw new IllegalArgumentException("Удаляемый listener не должен быть равен null");		
		listeners.remove(listener);
	}
}

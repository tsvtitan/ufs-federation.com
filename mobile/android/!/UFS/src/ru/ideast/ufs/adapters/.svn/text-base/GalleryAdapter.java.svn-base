package ru.ideast.ufs.adapters;

import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;

import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.managers.URLManager;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.polites.android.GestureImageView;
import com.polites.android.GesturePagerAdapter;

import android.content.Context;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;

public class GalleryAdapter extends PagerAdapter implements GesturePagerAdapter {

	private Context context;
	private List<NewsBean.Url> images;
	private HashMap<Integer, GestureImageView> map;
	private LinkedList<GestureImageView> viewPull;
	private OnClickListener onClickListener;
	private int currentPosition;
	
	public GalleryAdapter(Context context, List<NewsBean.Url> beans, OnClickListener listener) {
		this.context = context;
		images = beans;
		map = new HashMap<Integer, GestureImageView>();
		viewPull = new LinkedList<GestureImageView>();
		this.onClickListener = listener;
	}
	
	@Override
	public Object instantiateItem(View collection, int position) {
        LayoutParams params = new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT);
        
        GestureImageView view;
		if(!viewPull.isEmpty())
			view = viewPull.remove(0);
		else {
			view = new GestureImageView(context);
			view.setRecycle(true);
		}
        
        view.setLayoutParams(params);
        view.setAdjustViewBounds(true);
        view.setOnSingleTapListener(onClickListener);
        
        map.put(position, view);
        if(currentPosition == position)
        	initPage(position);

		((ViewPager) collection).addView(view, 0);
		return view;
	}
	
	@Override
	public void destroyItem(View collection, int position, Object view) {
		GestureImageView v = (GestureImageView) view;
        v.setImageBitmap(null);
		viewPull.add(v);
		map.remove(position);
        ((ViewPager) collection).removeView(v);
	}
	
	private void initPage(int position) {
		if(map.containsKey(position)) {
			clear();
			ImageLoader imageLoader = ImageLoader.getInstance();
			imageLoader.displayImage(URLManager.getFile(images.get(position).getUrl()), map.get(position));
		}
	}
	
	public void clear() {
		for(GestureImageView view: map.values()) {
			view.setImageBitmap(null);
		}
	}
	
	public void setCurrentPosition(int position) {
		this.currentPosition = position;
		initPage(position);
	}
	
	@Override
	public int getCount() {
		return images.size();
	}
	
	@Override
	public boolean isViewFromObject(View v, Object obj) {
		return v == obj;
	}

	@Override
	public GestureImageView getImage(int position) {
		return map.get(position);
	}
}

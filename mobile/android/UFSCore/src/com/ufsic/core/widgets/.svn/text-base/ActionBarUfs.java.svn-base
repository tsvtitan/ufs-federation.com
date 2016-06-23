package com.ufsic.core.widgets;

import com.ufsic.core.R;

import android.content.Context;
import android.content.res.TypedArray;
import android.graphics.drawable.Drawable;
import android.util.AttributeSet;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

public class ActionBarUfs extends RelativeLayout {
	
	private ImageView leftBtn;
	private TextView singleText;
	private LinearLayout textContainer;
	private TextView topText;
	private TextView bottomText;
	private ImageView rightBtn;
	
	public ActionBarUfs(Context context) {
		super(context);
		init(context);
	}
	
	public ActionBarUfs(Context context, AttributeSet attrs) {
		super(context, attrs);
		init(context);

		TypedArray typedArray = context.obtainStyledAttributes(attrs, R.styleable.ActionBarUfs);
		
		Drawable leftDrawable = typedArray.getDrawable(R.styleable.ActionBarUfs_left_btn);
		if(leftDrawable != null) {
			leftBtn.setImageDrawable(leftDrawable);
			leftBtn.setVisibility(View.VISIBLE);
		}
		
		String singleString = typedArray.getString(R.styleable.ActionBarUfs_single_text);
		if(singleString != null)
			setSingleText(singleString);
		
		String topString = typedArray.getString(R.styleable.ActionBarUfs_top_text);
		if(topString != null) {
			String bottomString = typedArray.getString(R.styleable.ActionBarUfs_bottom_text);
			if(bottomString != null)
				setDualText(topString, bottomString);
		}
		
		Drawable rightDrawable = typedArray.getDrawable(R.styleable.ActionBarUfs_right_btn);
		if(rightDrawable != null) {
			rightBtn.setImageDrawable(rightDrawable);
			rightBtn.setVisibility(View.VISIBLE);
		}
		
		Drawable backgroundDrawable = typedArray.getDrawable(R.styleable.ActionBarUfs_background);
		if(backgroundDrawable != null)
			setBackgroundDrawable(backgroundDrawable);
		else
			setBackgroundResource(R.drawable.actionbar);
		
		typedArray.recycle();
	}
	
	private void init(Context context) {
		inflate(context, R.layout.action_bar_ufs, this);
		
		leftBtn = (ImageView) findViewById(R.id.abu_left_btn);
		singleText = (TextView) findViewById(R.id.abu_single_text);
		textContainer = (LinearLayout) findViewById(R.id.abu_text_container);
		topText = (TextView) findViewById(R.id.abu_top_text);
		bottomText = (TextView) findViewById(R.id.abu_bottom_text);
		rightBtn = (ImageView) findViewById(R.id.abu_right_btn);
	}
	
	public void setLeftBtn(int resId) {
		leftBtn.setImageResource(resId);
		leftBtn.setVisibility(View.VISIBLE);
	}
	
	public void setSingleText(String text) {
		singleText.setText(text);
		singleText.setVisibility(View.VISIBLE);
		textContainer.setVisibility(View.GONE);
	}
	
	public void setDualText(String top, String bottom) {
		topText.setText(top);
		bottomText.setText(bottom);
		textContainer.setVisibility(View.VISIBLE);
		singleText.setVisibility(View.GONE);
	}
	
	public void setRightBtn(int resId) {
		rightBtn.setImageResource(resId);
		rightBtn.setVisibility(View.VISIBLE);
	}
	
	public void delRightBtn() {
		rightBtn.setVisibility(View.GONE);
	}
	
	public void setOnClickListener(OnClickListener listener) {
		leftBtn.setOnClickListener(listener);
		rightBtn.setOnClickListener(listener);
	}
}

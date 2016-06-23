package com.ufsic.core.fragments;

import java.util.ArrayList;
import java.util.Arrays;

import com.ufsic.core.activities.MainActivity;
import com.ufsic.core.activities.QRCodeActivity;
import com.ufsic.core.activities.TablesActivity;
import com.ufsic.core.adapters.LeftMenuListAdapter;
import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;

import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;


public class LeftMenuFragment extends Fragment implements OnItemClickListener {

	private LeftMenuListAdapter adapter;
	/* tsv */
	private String categoryId = null;
	private Fragment lastFragment = null;
	/* tsv */
	
	//public static LeftMenuFragment newInstance() {
	/* tsv */public static LeftMenuFragment newInstance(String categoryId) {
		LeftMenuFragment ret = new LeftMenuFragment();
		ret.categoryId = categoryId;
		return ret;
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View v = inflater.inflate(R.layout.left_menu_fragment, container, false);
		
		ArrayList<CategoryBean.Result> beans = DatabaseManager.INSTANCE.getList(CategoryBean.Result.class);
		
		ListView list = (ListView) v.findViewById(R.id.left_menu_list);
		list.setOnItemClickListener(this);
		adapter = new LeftMenuListAdapter(getActivity());
		adapter.setData(beans);
		list.setAdapter(adapter);

		if (!ToolBox.isEmpty(categoryId) && beans!=null) {
			
			CategoryBean.Result categoryBean = null;
			for (CategoryBean.Result bean: beans) {
				String catId = bean.getId();
				if (categoryId.equals(catId)) {
					categoryBean = bean;
					break;
				}
			}
			lastFragment = showActivityByBean(categoryBean,-1,false);
		}
		return v;
	}  

	/* tsv */
	public Fragment getLastFragment() {
		return lastFragment;
	}
	
	private Fragment showActivityByBean(CategoryBean.Result bean, int position, boolean showContent) {
	
		if (bean==null) return null;
		
		Fragment newFragment = null;
		MainActivity mainActivity = (MainActivity) getActivity();
		
		if(ToolBox.isEmpty(bean.getSubcategories())) {
			
			String s = bean.getParentTitle();
			String[] titles = (s!=null)?new String[]{s,bean.getTitle()}:new String[]{bean.getTitle()};
			AnalyticsCounter.eventScreen(MainActivity.ScreenName,titles,null,null);
			
			switch (bean.getType()) {
			case 0:
				break;
			case 1:
				String categoryId = adapter.getCategoryId(position);
				if (bean.getId().equals(categoryId))
					newFragment = NewsListFragment.newInstance(categoryId, "", titles);
				else
					newFragment = NewsListFragment.newInstance(categoryId, bean.getId(), titles);
				break;
			case 2:
				newFragment = ActivityListFragment.newInstance(bean.getId(),titles);
				break;
			case 3:
				Intent intent = new Intent(getActivity(), TablesActivity.class);
				intent.putExtra(TablesActivity.TITLE, bean.getTitle());
				intent.putExtra(TablesActivity.SUBCATEGORY_ID, bean.getId());
				intent.putExtra(TablesActivity.TITLES,titles);
				startActivity(intent);
				break;
			case 4:
				newFragment = GroupPreviewListFragment.newInstance(bean.getId(),titles);
				break;
			case 5:
				newFragment = HtmlFragment.newInstance(bean.getId(), "");
				break;
			case 6:
				newFragment = BranchListFragment.newInstance(titles);
				break;
			case 99:
				/*Intent qrcode = new Intent(getActivity(), QRCodeActivity.class);
				qrcode.putExtra(QRCodeActivity.TITLE, bean.getTitle());
				startActivity(qrcode);*/
				newFragment = QRCodeFragment.newInstance(bean.getTitle().toString());
				break;
			}
		}
		else {
			newFragment = SubcategoryListFragment.newInstance(bean.getId(),bean.getTitle(),bean.getSubcategories());
		}
		
		if (newFragment != null) {
			
			mainActivity.switchContent(newFragment,showContent);
			mainActivity.setActionBarText(bean.getTitle());
			
			if(bean.getType() == 1)
				mainActivity.setActionBarRightBtn(true);
			else
				mainActivity.setActionBarRightBtn(false);
		}
		
		return newFragment;
	}
	/* tsv */
	
	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int pos, long id) {
		
		lastFragment = showActivityByBean(adapter.getItem(pos),pos,true);
	}
}

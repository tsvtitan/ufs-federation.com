package com.ufsic.core.fragments;

import java.util.ArrayList;

import com.ufsic.core.activities.MainActivity;
import com.ufsic.core.activities.TablesActivity;
import com.ufsic.core.adapters.SubcategoryListAdapter;
import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.counters.AnalyticsCounter;

import com.ufsic.core.R;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;

public class SubcategoryListFragment extends Fragment implements OnItemClickListener {
	
	private static final String CATEGORY_ID = "categoryId";
	/* tsv */private static final String CATEGORY_TITLE = "categoryTitle";
	private static final String SUBCATEGORIES = "subcategories";
	
	private String categoryId;
	/* tsv */private String categoryTitle;
	private SubcategoryListAdapter adapter;
	
	//public static SubcategoryListFragment newInstance(String categoryId, ArrayList<CategoryBean.Result> beans) {
	/* tsv */public static SubcategoryListFragment newInstance(String categoryId, String categoryTitle, ArrayList<CategoryBean.Result> beans) {
		SubcategoryListFragment fragment = new SubcategoryListFragment();
		
		Bundle args = new Bundle();
		args.putString(CATEGORY_ID, categoryId);
		/* tsv */args.putString(CATEGORY_TITLE, categoryTitle);
		args.putSerializable(SUBCATEGORIES, beans);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.list_only_fragment, container, false);
		
		adapter = new SubcategoryListAdapter(getActivity());
		
		Bundle args = getArguments();
		//if(args != null && args.containsKey(CATEGORY_ID) && args.containsKey(SUBCATEGORIES)) {
		/* tsv */if(args != null && args.containsKey(CATEGORY_ID) && args.containsKey(CATEGORY_TITLE) && args.containsKey(SUBCATEGORIES)) {
			categoryId = args.getString(CATEGORY_ID);
			/* tsv */categoryTitle = args.getString(CATEGORY_TITLE);
			adapter.setData((ArrayList<CategoryBean.Result>) args.getSerializable(SUBCATEGORIES));
		}
		
		ListView list = (ListView) root.findViewById(R.id.lof_list);
		
		View emptyView = root.findViewById(R.id.lof_progress);
		list.setEmptyView(emptyView);
		
		list.setOnItemClickListener(this);
		list.setAdapter(adapter);
		
		return root;
	}

	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int pos, long id) {
		Fragment newFragment = null;
		MainActivity mainActivity = (MainActivity) getActivity();
		
		CategoryBean.Result bean = adapter.getItem(pos);
		
		/* tsv */
		String[] titles = new String[]{categoryTitle,bean.getTitle()};
		AnalyticsCounter.eventScreen(MainActivity.ScreenName,titles,null,null);
		/* tsv */
		
		switch (bean.getType()) {
		case 0:
			break;
		case 1:
			//newFragment = NewsListFragment.newInstance(categoryId, bean.getId());
			/* tsv */newFragment = NewsListFragment.newInstance(categoryId,bean.getId(),titles);
			break;
		case 2:
			//newFragment = ActivityListFragment.newInstance(categoryId);
			newFragment = ActivityListFragment.newInstance(categoryId,titles);
			break;
		case 3:
			Intent intent = new Intent(getActivity(), TablesActivity.class);
			intent.putExtra(TablesActivity.TITLE, bean.getTitle());
			intent.putExtra(TablesActivity.SUBCATEGORY_ID, bean.getId());
			/* tsv */intent.putExtra(TablesActivity.TITLES,titles);
			startActivity(intent);
			break;
		case 4:
			//newFragment = GroupPreviewListFragment.newInstance(bean.getId());
			/* tsv */newFragment = GroupPreviewListFragment.newInstance(bean.getId(),titles);
			break;
		case 5:
			newFragment = HtmlFragment.newInstance(categoryId, bean.getId());
			break;
		case 6:
			//newFragment = BranchListFragment.newInstance();
			/* tsv */newFragment = BranchListFragment.newInstance(titles);
			break;
		}
		
		if (newFragment != null) {
			mainActivity.switchContent(newFragment);
			mainActivity.setActionBarText(bean.getTitle());
			
			if(bean.getType() == 1)
				mainActivity.setActionBarRightBtn(true);
			else
				mainActivity.setActionBarRightBtn(false);
		}
	}
}

package ru.ideast.ufs.fragments;

import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.activities.MainActivity;
import ru.ideast.ufs.activities.TablesActivity;
import ru.ideast.ufs.adapters.SubcategoryListAdapter;
import ru.ideast.ufs.beans.CategoryBean;
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
	private static final String SUBCATEGORIES = "subcategories";
	
	private String categoryId;
	private SubcategoryListAdapter adapter;
	
	public static SubcategoryListFragment newInstance(String categoryId, ArrayList<CategoryBean.Result> beans) {
		SubcategoryListFragment fragment = new SubcategoryListFragment();
		
		Bundle args = new Bundle();
		args.putString(CATEGORY_ID, categoryId);
		args.putSerializable(SUBCATEGORIES, beans);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.list_only_fragment, container, false);
		
		adapter = new SubcategoryListAdapter(getActivity());
		
		Bundle args = getArguments();
		if(args != null && args.containsKey(CATEGORY_ID) && args.containsKey(SUBCATEGORIES)) {
			categoryId = args.getString(CATEGORY_ID);
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
		switch (bean.getType()) {
		case 0:
			break;
		case 1:
			newFragment = NewsListFragment.newInstance(categoryId, bean.getId());
			break;
		case 2:
			newFragment = ActivityListFragment.newInstance(categoryId);
			break;
		case 3:
			Intent intent = new Intent(getActivity(), TablesActivity.class);
			intent.putExtra(TablesActivity.TITLE, bean.getTitle());
			intent.putExtra(TablesActivity.SUBCATEGORY_ID, bean.getId());
			startActivity(intent);
			break;
		case 4:
			newFragment = GroupPreviewListFragment.newInstance(bean.getId());
			break;
		case 5:
			newFragment = HtmlFragment.newInstance(categoryId, bean.getId());
			break;
		case 6:
			newFragment = BranchListFragment.newInstance();
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

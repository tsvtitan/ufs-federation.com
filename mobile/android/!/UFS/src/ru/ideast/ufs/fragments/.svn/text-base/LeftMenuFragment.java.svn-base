package ru.ideast.ufs.fragments;

import ru.ideast.ufs.R;
import ru.ideast.ufs.activities.MainActivity;
import ru.ideast.ufs.activities.QRCodeActivity;
import ru.ideast.ufs.activities.TablesActivity;
import ru.ideast.ufs.adapters.LeftMenuListAdapter;
import ru.ideast.ufs.beans.CategoryBean;
import ru.ideast.ufs.db.DatabaseManager;
import ru.ideast.ufs.utils.ToolBox;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;
import android.widget.Toast;

public class LeftMenuFragment extends Fragment implements OnItemClickListener {

	private LeftMenuListAdapter adapter;
	
	public static LeftMenuFragment newInstance() {
		return new LeftMenuFragment();
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View v = inflater.inflate(R.layout.left_menu_fragment, container, false);
		
		ListView list = (ListView) v.findViewById(R.id.left_menu_list);
		list.setOnItemClickListener(this);
		adapter = new LeftMenuListAdapter(getActivity());
		adapter.setData(DatabaseManager.INSTANCE.getList(CategoryBean.Result.class));
		list.setAdapter(adapter);
		
		return v;
	}  

	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int pos, long id) {
		Fragment newFragment = null;
		MainActivity mainActivity = (MainActivity) getActivity();
		
		CategoryBean.Result bean = adapter.getItem(pos);
		if(ToolBox.isEmpty(bean.getSubcategories())) {
			switch (bean.getType()) {
			case 0:
				break;
			case 1:
				String categoryId = adapter.getCategoryId(pos);
				if(bean.getId().equals(categoryId))
					newFragment = NewsListFragment.newInstance(categoryId, "");
				else
					newFragment = NewsListFragment.newInstance(categoryId, bean.getId());
				break;
			case 2:
				newFragment = ActivityListFragment.newInstance(bean.getId());
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
				newFragment = HtmlFragment.newInstance(bean.getId(), "");
				break;
			case 6:
				newFragment = BranchListFragment.newInstance();
				break;
			/* tsv */
			case 99:
				Intent qrcode = new Intent(getActivity(), QRCodeActivity.class);
				qrcode.putExtra(QRCodeActivity.TITLE, bean.getTitle());
				qrcode.putExtra(QRCodeActivity.SUBCATEGORY_ID, bean.getId());
				startActivity(qrcode);
				break;
			/* tsv */
			}
		}
		else {
			newFragment = SubcategoryListFragment.newInstance(bean.getId(), bean.getSubcategories());
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

package com.ufsic.core.fragments;

import com.inqbarna.tablefixheaders.TableFixHeaders;
import com.ufsic.core.adapters.TableAdapter;
import com.ufsic.core.beans.TableBean;

import com.ufsic.core.R;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

public class TableFragment extends Fragment {
	
	public static final String TABLE = "table";
	/* tsv */public static final String TITLES = "titles";
	
	//public static TableFragment newInstance(TableBean.Table table) {
	/* tsv */public static TableFragment newInstance(TableBean.Table table, String[] titles) {
		TableFragment fragment = new TableFragment();
		
		Bundle args = new Bundle();
		args.putSerializable(TABLE, table);
		args.putStringArray(TITLES, titles);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.table_fragment, container, false);
		
		Bundle args = getArguments();
		if(args != null && args.containsKey(TABLE) && args.containsKey(TITLES)) {
			TableAdapter adapter = new TableAdapter(getActivity());
			TableFixHeaders table = (TableFixHeaders) root.findViewById(R.id.tf_table);
			
			TableBean.Table data = (TableBean.Table) args.getSerializable(TABLE);
			
			//adapter.setData(data);
			/* tsv */adapter.setData(data,args.getStringArray(TITLES));
			table.setAdapter(adapter);
		}
		
		return root;
	}
}

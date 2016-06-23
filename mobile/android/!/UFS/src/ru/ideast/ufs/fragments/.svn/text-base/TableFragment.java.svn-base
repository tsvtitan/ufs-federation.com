package ru.ideast.ufs.fragments;

import com.inqbarna.tablefixheaders.TableFixHeaders;

import ru.ideast.ufs.R;
import ru.ideast.ufs.adapters.TableAdapter;
import ru.ideast.ufs.beans.TableBean;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

public class TableFragment extends Fragment {
	
	public static final String TABLE = "table";
	
	public static TableFragment newInstance(TableBean.Table table) {
		TableFragment fragment = new TableFragment();
		
		Bundle args = new Bundle();
		args.putSerializable(TABLE, table);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.table_fragment, container, false);
		
		Bundle args = getArguments();
		if(args != null && args.containsKey(TABLE)) {
			TableAdapter adapter = new TableAdapter(getActivity());
			TableFixHeaders table = (TableFixHeaders) root.findViewById(R.id.tf_table);
			
			TableBean.Table data = (TableBean.Table) args.getSerializable(TABLE);
			
			adapter.setData(data);
			table.setAdapter(adapter);
		}
		
		return root;
	}
}

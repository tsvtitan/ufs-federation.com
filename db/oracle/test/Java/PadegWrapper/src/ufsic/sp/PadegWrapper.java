package ufsic.sp;

import padeg.lib.*;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author zrv
 */
public class PadegWrapper {
    
    public static void getFioPartsStr(String fio, 
            String []lastName, String []firstName, String []middleName){
        FIO fioObj = new FIO("", "", "");
        Padeg.getFioParts(fio, fioObj);
        firstName[0] = fioObj.firstName;
        lastName[0] = fioObj.lastName;
        middleName[0] = fioObj.middleName;
    }
    
    /* Для теста */
    public static void main(String [] args){
        
        String []firstName = {""};
        String []lastName = {""};
        String []middleName = {""}; 
                
        getFioPartsStr("Иванов Иван Иванович", lastName, firstName, middleName);                
        System.out.println(lastName[0] + " " + firstName[0] + " " + middleName[0]);
    }
            
}

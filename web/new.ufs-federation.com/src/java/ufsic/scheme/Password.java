/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package ufsic.scheme;

/**
 *
 * @author zrv
 */
public class Password {
  private String oldPassword;
  private String newPassword;
  private String confirmedPassword;
  
  private String sucessMessage;
  private String failMessage;

  public String getOldPassword() {
    return oldPassword;
  }

  public void setOldPassword(String oldPassword) {
    this.oldPassword = oldPassword;
  }

  public String getNewPassword() {
    return newPassword;
  }

  public void setNewPassword(String newPassword) {
    this.newPassword = newPassword;
  }

  public String getConfirmedPassword() {
    return confirmedPassword;
  }

  public void setConfirmedPassword(String confirmedPassword) {
    this.confirmedPassword = confirmedPassword;
  }
  
  public String getSucessMessage() {
    return sucessMessage;
  }

  public void setSucessMessage(String sucessMessage) {
    this.sucessMessage = sucessMessage;
  }

  public String getFailMessage() {
    return failMessage;
  }

  public void setFailMessage(String failMessage) {
    this.failMessage = failMessage;
  }  
  
}

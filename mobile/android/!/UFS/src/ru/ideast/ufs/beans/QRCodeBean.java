package ru.ideast.ufs.beans;

import java.io.Serializable;
import java.util.ArrayList;

import ru.ideast.ufs.activities.PromotionActivity;
import ru.ideast.ufs.beans.QRCodeBean.Result;

public class QRCodeBean extends BaseBean<Result> {

	public static enum KindType {
	
		UNKNOWN, MESSAGE, REDIRECTION, PROMOTION;
		
		public static KindType get(String kind) {
    		
			KindType ret = UNKNOWN;
    		if (kind!=null) {
				if (kind.equals("redirection")) {
					ret = REDIRECTION;
				} else if (kind.equals(PromotionActivity.PROMOTION)) {
					ret = PROMOTION;
				} else if (kind.equals("message")) {
					ret = MESSAGE;
				}
			}
    		return ret;
    	}
	}
	
	public static class Result implements Serializable {
		
		private static final long serialVersionUID = -2980283947043913395L;
		
		private String kind;
		private Message message;
		private Redirection redirection;
		private Promotion promotion;
		
		public String getKind() {
			return kind;
		}
		
		public KindType getKindType() {
			return KindType.get(kind);
		}
		
		public void setKind(String kind) {
			this.kind = kind;
		}
	
		public Message getMessage() {
			return message; 
		}
		
		public void setMessage(Message message) {
			this.message = message;
		}
		
		public Redirection getRedirection() {
			return redirection; 
		}
		
		public void setRedirection(Redirection redirection) {
			this.redirection = redirection;
		}
		
		public Promotion getPromotion() {
			return promotion; 
		}
		
		public void setPromotion(Promotion promotion) {
			this.promotion = promotion;
		}
	}
	
	public static class Message implements Serializable {
		
		
		private static final long serialVersionUID = 6708758078719822086L;
		
		private String text;
		
		public String getText() {
			return text;
		}
		
		public void setText(String text) {
			this.text = text;
		}

	}
	
    public static class Redirection implements Serializable {
		
		
		private static final long serialVersionUID = 8175837494535782957L;
		
		private String url;
		
		public String getUrl() {
			return url;
		}
		
		public void setUrl(String url) {
			this.url = url;
		}

	}
    
    public static class Promotion implements Serializable {
		
		
		private static final long serialVersionUID = -8367417845158780386L;
		
		private String title;
		private ArrayList<Product> products;
		
		public String getTitle() {
			return title;
		}
		
		public void setTitle(String title) {
			this.title = title;
		}
		
		public ArrayList<Product> getProducts() {
			return products;
		}
		
		public void setProduct(ArrayList<Product> products) {
			this.products = products;
		}

	   public static class Product implements Serializable {
			
		    public static enum StatusType {
		    	
		    	UNKNOWN, STARTED, PREPARED, FINISHED, DISABLED, ACCEPTED, REJECTED, EXPIRED;
				
				public static StatusType get(String status) {
		    		
					StatusType ret = UNKNOWN;
		    		if (status!=null) {
						if (status.equals("started")) {
							ret = STARTED;
						} else if (status.equals("prepared")) {
							ret = PREPARED;
						} else if (status.equals("finished")) {
							ret = FINISHED;
						} else if (status.equals("disabled")) {
							ret = DISABLED;
						} else if (status.equals("accepted")) {
							ret = ACCEPTED;
						} else if (status.equals("rejected")) {
							ret = REJECTED;
						} else if (status.equals("expired")) {
							ret = EXPIRED;
						}
					}
		    		return ret;
		    	}
		    }
			
			private static final long serialVersionUID = -5881557031550637986L;
			
			private String name;
			private String description;
			private String agreement;
			private String imageURL;
			private String promotionID;
			private String status;
			private Integer countdown;
			
			public String getName() {
				return name;
			}
			
			public void setName(String name) {
				this.name = name;
			}
			
			public String getDescription() {
				return description;
			}
			
			public void setDescription(String description) {
				this.description = description;
			}
			
			public String getAgreement() {
				return agreement;
			}
			
			public void setAgreement(String agreement) {
				this.agreement = agreement;
			}
			
			public String getImageURL() {
				return imageURL;
			}
			
			public void setImageURL(String imageURL) {
				this.imageURL = imageURL;
			}

			public String getPromotionID() {
				return promotionID;
			}
			
			public void setPromotionID(String promotionID) {
				this.promotionID = promotionID;
			}
			
			public String getStatus() {
				return status;
			}
			
			public StatusType getStatusType() {
				return StatusType.get(status);
			}
			
			public void setStatus(String status) {
				this.status = status;
			}
			
			public Integer getCountdown() {
				return countdown;
			}
			
			public void setCountdown(Integer countdown) {
				this.countdown = countdown;
			}
		}
	}
    
 
    
    
}

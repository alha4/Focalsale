
    
 //https://github.com/alha4/market.git
 
 function JCart() {
    
    this.storage = this.storageAdapter();
    
    this.cartItems = [];
    
    var storage_hash  = 'cart_items',
    
        _jcart = this,
      
        get = function() {
       
           return JSON.parse( _jcart.storage.getItem('cart_items') );
         
       }
    
    var in_cart = function(needle) { 

     var strict = true;
     
     needle = JSON.parse(needle);
     
     needle = needle.item_id;
     
     var items = get();
     
     for(var key in items) {

       var haystack = JSON.parse(items[key]);
       
         if( (strict && (haystack).item_id === needle) || (!strict && (haystack).item_id == needle) ){

            return key;

         } 
      }
      return false; 
   }
   
   this.add_class = '.add-to-cart';
   this.buy_class = '.buy-it-now';
   
   $(this.add_class).bind("click",function(e){
      
    if( !in_cart( $(e.target).attr("data-cart") ) ) { 
      _jcart.addToCart( String($(e.target).attr("data-cart")) );
    } else {
      alert("item in basket");
    }
      
      //alert(2);
   });
   
    
 }
 
 JCart.prototype = {

  storageAdapter : function() {

  var  storage = window.sessionStorage || window.localStorage || null;

  if( storage === null ) {
 
      
      storage = { setItem : function(key,item) {

                    $.cookie(key,item);

                 },
                 getItem : function(key) {

                   return $.cookie(key);

                 },
                 clear : function(key) {
 
                   $.cookie(key,null);
                        
                  }
               };    
  }
  
  return storage;

  },
  
  addToCart : function(data) {

    var required_params = ["item_id", "item_name","price","shipping_cost","picture","currency"],
        owner_params    = ["shipping_method","owner"],
        error_params    = [];
        
    try {
        
      data = JSON.parse(data);
      
      if(typeof data != 'object') {
          throw Error("Not valid params");   //code
      }
      
    } catch(e) {
        
      console.log(data);
    }
    //console.log(data);
    
    for(var prop in required_params) {
        
        if(!data.hasOwnProperty(required_params[prop]) ) {
          
           throw new Error("Invalid cart property");//code
        }
   }
   
   if(data.length > 1) {
      
      var current_items = get();
      
          current_items.push(data);
          
      this.cartItems = current_items ;
      
   } else {
      
      this.cartItems.push(JSON.stringify(data));
   }
   
   this.storage.setItem("cart_items",JSON.stringify(this.cartItems));
    
  }
 };

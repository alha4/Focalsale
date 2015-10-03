(function(global,$) {
  console.log($);
  global.$ = $;
  
  function JCart() {
    
    this.storage = window.sessionStorage || window.localStorage || { setItem : function(key,item) {

                         $.cookie(key,item);

                      },
                      getItem : function(key) {

                          return $.cookie(key);

                      },
                      clear : function(key) {
 
                           $.cookie(key,null);
                       }
                    };    
  
    this.cartItems = {items : []};
    
    var storage_hash = 'cart_items',
    
        _jcart  = this,
      
        in_cart = function(needle) { 

          needle = JSON.parse(needle);
     
          needle = needle.item_id;
     
          var storage_items = _jcart.get();
     
          if(!storage_items) {
      
             return false;
          }
      
          for(var key in storage_items) {

            var haystack = storage_items[key];
            //console.log( typeof (haystack) );  
            if( haystack.item_id === needle ){
               
              return key;
            
            } 
          }
          
          return false; 
        }
   
   this.add_class    = '.add-to-cart';
   this.buy_class    = '.buy-it-now';
   this.remove_class = '.remove-cart-item';
   
   $(this.remove_class).bind("click",function(e){
      
      _jcart.remove( String($(e.target).attr("data-cart")) );
      
   });
   
   $(this.add_class).bind("click",function(e){
      
    if( !in_cart( $(e.target).attr("data-cart") ) ) { 
        _jcart.addToCart( String($(e.target).attr("data-cart")) );
    } else {
      alert("item in basket");
   }   
    
  });
     
 }
 
 JCart.prototype = {
   
  init : function() {
   
  },
  get : function() {
       
      var storageToObj = JSON.parse( this.storage.getItem('cart_items') );
      
      return (storageToObj === null) ? false : storageToObj.items;
         
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
      
      var current_items = this.get();
      
          current_items.push(data);
          
      this.cartItems.items = current_items;
      
   } else {
      
      this.cartItems.items.push( data );
   }
   //console.log( ( this.cartItems) );
   
   this.storage.setItem("cart_items",JSON.stringify(this.cartItems));
   
  },
  remove : function(item) {
   
     var current_items = this.get();
      
         item =  JSON.parse(item);
         
     var item_id = item.item_id,
         new_items = [];
     
     for(var k in current_items) {
      
         var current_item = current_items[k];
         
         if(current_item.item_id !== item_id) {
            
            new_items.push(current_item);
            //code
         }
      
     }
     
     this.cartItems.items = new_items;
     
     this.storage.setItem("cart_items",JSON.stringify(this.cartItems) );
     //console.log( this.get() );
  },
  
  clear : function() {
      
    this.storage.removeItem("cart_items");
    this.storage.clear();
    
  }
 }
  var jcart = new JCart();
  
  global.JCart =  {
       get : jcart.get 
  }
})(window,$); 
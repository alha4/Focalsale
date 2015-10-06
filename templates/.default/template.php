<ul class="category">
<?
 foreach($arResult  as $item ) {
      if( is_array($item['subcategories'])) {

          echo '<li><a href="/list.php?sect=',rawurlencode($item['name']),'">',str_replace("&","&amp;",$item['name']),'</a><ul class="sub-category">';

           foreach($item['subcategories'] as $innerItem) {
            
               if( $innerItem['name'] == 'Android Car DVD Player' || $innerItem['name']  == 'Rugged Phones' || $innerItem['name'] == 'Android Watch') 
                     continue;
                echo '<li><a href="/list.php?sect=', rawurlencode( $innerItem['name']),'">', str_replace("&","&amp;",$innerItem['name']),'</a></li>';
          }

          echo '</ul>';
        
     }
      else {
        echo '<li><a href="/list.php?sect=', rawurlencode($item['name']) ,'">', str_replace("&","&amp;",$item['name']), '</a></li>';
        $cat[] = $item['name'];
      }
  }
?>
</ul>
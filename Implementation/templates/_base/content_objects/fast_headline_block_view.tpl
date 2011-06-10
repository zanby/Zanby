<div class="prInner" style="overflow:auto;
              {if $font_family}font-family:{$font_family};{/if}
              {if $font_size}font-size:{$font_size}px;{/if}
              {if $font_weight_bold}font-weight:bold;{/if}
              {if $font_style_italic}font-style:italic;{/if}
              {if $text_decoration_underline}text-decoration:underline;{/if}
              {if $text_align}text-align:{$text_align};{/if}
              {if $color}color:{$color};{/if}
              
              ">{$Content|escape:"html"}</div>

{*popup_item*}
<table>
  <tr>
    <td>{t}M = Total number if individuals in the group that is joining family{/t}</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>{t}Y = Total number of individuals in Group Family{/t}</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>{t}P = M+Y{/t}</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>{t}G = Monthly price of group family membership{/t}</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>{t}G = ((P/(M+Y)) * M{/t}</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>{t}Example{/t}</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>{t}M = Total number of individuals in the group that is joining family{/t}</td><td>{$exampleGroupMembers}</td>
  </tr>
  <tr>
    <td>{t}Y = Total number of individuals in Group Family{/t}</td><td>{$exampleFamilyMembers}</td>
  </tr>
  <tr>
    <td>{t}P = Total Monthly Price of Group Family{/t}</td><td>${$exampleFamilyFee|string_format:"%.2f"} {t}USD/month{/t}</td>
  </tr>
  <tr>
    <td>{t}G = Monthly price of group family membership{/t}</td><td>${$exampleGroupFee|string_format:"%.2f"} {t}USD/month{/t}</td>
  </tr>
</table>

<div class="prInnerTop prTCenter">  
{t var="in_button"}Close{/t}  
{linkbutton style="" name=$in_button onclick="popup_window.close(); return false;"}   
</div>
{*popup_item*}
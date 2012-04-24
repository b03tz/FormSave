<h2>[[%formsave.form]] [[+topic:ucfirst]]</h2>

<b>[[%formsave.ip]]</b>: [[+ip]]<br />
<b>[[%formsave.date]]</b>: [[%formsave.dateformat?
  &d=`[[+time:date=`%d`]]`
  &m=`[[+time:date=`%m`]]`
  &y=`[[+time:date=`%Y`]]`
  &h=`[[+time:date=`%H`]]`
  &m=`[[+time:date=`%M`]]`
  &s=`[[+time:date=`%S`]]`
]]<br />
<b>[[%formsave.published]]</b>: [[+published:eq=`1`:then=`[[%formsave.published_yes]]`:else=`[[%formsave.published_no]]`]]
<br /><br />
<table width="100%" class="fs-table">
[[+content]]
</table>
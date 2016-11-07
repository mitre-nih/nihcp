<?php
$entity = elgg_extract('entity', $vars);
echo "<h4 class='pvs'><div>Changed from $entity->from_status to $entity->to_status</h3>";

echo "<div class='pvs'>";
echo "<div><b>Actor</b></div>";
echo "<div>{$entity->getOwnerEntity()->getDisplayName()}</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Date</b></div>";
$dt = date('D, d M Y H:i:s', $entity->getTimeCreated());
echo "<div>$dt</div>";
echo "</div>";

echo "<div class='pvs'>";
echo "<div><b>Reason</b></div>";
echo "<div>$entity->reason</div>";
echo "</div>";
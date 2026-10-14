<?php
require_once __DIR__ . '/../src/inc.php';
require_login();

$q  = trim($_GET['q']     ?? '');
$st = $_GET['status']     ?? '';
$tp = $_GET['type']       ?? '';

$sql  = "SELECT a.id,a.account_no,a.type,a.status,a.balance,a.opened_at,a.funding_status,
                c.first_name,c.last_name,c.cif,c.id_number
           FROM accounts a JOIN customers c ON c.id=a.customer_id WHERE 1=1";
$args = [];
if ($q !== '') {
    $sql .= " AND ((c.first_name||' '||c.last_name) LIKE ? OR a.account_no LIKE ? OR c.id_number LIKE ? OR c.phone LIKE ?)";
    $args = ["%$q%","%$q%","%$q%","%$q%"];
}
if (in_array($st,['Pending','Active','Closed','Rejected'],true)) { $sql.=" AND a.status=?"; $args[]=$st; }
if ($tp !== '') { $sql.=" AND a.type=?"; $args[]=$tp; }
$s = $db->prepare($sql." ORDER BY a.id DESC LIMIT 200");
$s->execute($args);
$rows = $s->fetchAll();
$types = array_keys(ACCOUNT_TYPES);
$status_cls = ['Active'=>'t-ok','Pending'=>'t-brand','Closed'=>'t-mute','Rejected'=>'t-bad'];

page_top('Accounts');
?>
<div class="page-heading">
  <div><h1>Accounts</h1><p class="sub">Search and manage all savings accounts.</p></div>
  <?php if(role()==='officer'): ?>
  <div class="page-heading-actions">
    <a class="btn gold" href="<?= url('new.php') ?>"><?= svg_icon('plus') ?> New Account</a>
  </div>
  <?php endif; ?>
</div>

<div class="filter-bar">
  <form method="get" style="display:contents;">
    <label style="flex:2;min-width:180px;margin-bottom:0;">
      <div class="input-wrap" style="margin-top:0;">
        <span class="input-icon"><?= svg_icon('search') ?></span>
        <input name="q" value="<?= e($q) ?>" placeholder="Name, account number, ID or phone…"
               style="margin-top:0;padding-left:34px;">
      </div>
    </label>
    <label style="flex:1;min-width:120px;margin-bottom:0;">All Types
      <select name="type" style="margin-top:4px;">
        <option value="">All Types</option>
        <?php foreach($types as $t): ?>
          <option<?= $tp===$t?' selected':'' ?>><?= e($t) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label style="flex:1;min-width:110px;margin-bottom:0;">All Statuses
      <select name="status" style="margin-top:4px;">
        <option value="">All Statuses</option>
        <?php foreach(['Pending','Active','Closed','Rejected'] as $o): ?>
          <option<?= $st===$o?' selected':'' ?>><?= $o ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="btn" type="submit" style="align-self:flex-end;"><?= svg_icon('search') ?> Search</button>
    <?php if($q||$st||$tp): ?>
      <a class="btn alt sm" href="<?= url('accounts.php') ?>" style="align-self:flex-end;">Clear</a>
    <?php endif; ?>
  </form>
</div>

<div class="card">
  <?php if(!$rows): ?>
    <p class="empty">No accounts match your search.</p>
  <?php else: ?>
  <div style="font-size:11px;color:var(--muted);margin-bottom:8px;">Showing <?= count($rows) ?> result(s)</div>
  <div class="scroll">
    <table>
      <thead><tr>
        <th>Account No.</th><th>Customer Name</th><th>ID Number</th>
        <th>Account Type</th><th>Opened</th><th class="num">Balance (ETB)</th>
        <th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php foreach($rows as $a): $cls=$status_cls[$a['status']]??'t-mute'; ?>
        <tr>
          <td style="font-weight:700;font-family:monospace;font-size:12px;">
            <?= $a['account_no']?e($a['account_no']):'<span class="muted">Pending</span>' ?></td>
          <td>
            <a href="<?= url('account.php?id=' . (int)$a['id']) ?>" style="font-weight:700;color:var(--ink);">
              <?= e($a['first_name'].' '.$a['last_name']) ?>
            </a><br>
            <span style="font-size:11px;color:var(--muted);"><?= e($a['cif']) ?></span>
          </td>
          <td style="font-size:12px;color:var(--muted);"><?= e($a['id_number']) ?></td>
          <td style="font-size:12px;"><?= e($a['type']) ?></td>
          <td style="font-size:12px;color:var(--muted);"><?= e($a['opened_at']?:'—') ?></td>
          <td class="num"><?= $a['status']==='Active'?birr($a['balance']):'—' ?></td>
          <td>
            <?php if($a['funding_status']==='Overdue'): ?>
              <span class="tag t-bad" style="display:block;margin-bottom:3px;">Overdue</span>
            <?php elseif($a['funding_status']==='Awaiting funding'&&$a['status']==='Active'): ?>
              <span class="tag t-warn" style="display:block;margin-bottom:3px;">Awaiting Funding</span>
            <?php endif; ?>
            <span class="tag <?= $cls ?>"><?= e($a['status']) ?></span>
          </td>
          <td><a class="btn xs <?= $a['status']==='Pending'?'gold':'' ?>"
                 href="<?= $a['status']==='Pending' ? url('approvals.php') : url('account.php?id='.(int)$a['id']) ?>">
            <?= $a['status']==='Pending'?'Review':'View' ?></a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php page_bottom(); ?>

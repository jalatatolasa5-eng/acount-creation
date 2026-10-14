<div class="flash-wrap">
  <?php foreach ($data['flash'] as [$type, $msg]): ?>
    <div class="msg <?= $type === 'err' ? 'msg-err' : 'msg-ok' ?>" role="status">
      <?= svg_icon($type === 'err' ? 'warn' : 'ok-ic') ?>
      <?= e($msg) ?>
    </div>
  <?php endforeach; ?>
</div>

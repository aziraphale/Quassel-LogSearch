<?php
$errorFlashes   = $this->flashes('error');
$infoFlashes    = $this->flashes('info');
$successFlashes = $this->flashes('success');
?>

<?php if ($errorFlashes || $infoFlashes || $successFlashes): ?>
    <section id="flashes">
        <?php if ($errorFlashes): ?>
            <aside id="error_flashes">
                <?php foreach ($errorFlashes as $flashMessage): ?>
                    <div class="alert alert-danger">
                        <?=$flashMessage?>
                    </div>
                <?php endforeach; ?>
            </aside>
        <?php endif; ?>

        <?php if ($infoFlashes): ?>
            <aside id="info_flashes">
                <?php foreach ($infoFlashes as $flashMessage): ?>
                    <div class="alert alert-info">
                        <?=$flashMessage?>
                    </div>
                <?php endforeach; ?>
            </aside>
        <?php endif; ?>

        <?php if ($successFlashes): ?>
            <aside id="success_flashes">
                <?php foreach ($successFlashes as $flashMessage): ?>
                    <div class="alert alert-success">
                        <?=$flashMessage?>
                    </div>
                <?php endforeach; ?>
            </aside>
        <?php endif; ?>
    </section>
<?php endif; ?>

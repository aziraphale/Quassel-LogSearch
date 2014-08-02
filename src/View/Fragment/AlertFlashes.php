<?php if ($errorFlashes = $this->flashes('error')): ?>
    <aside id="error_flashes">
        <?php foreach ($errorFlashes as $flashMessage): ?>
            <div class="alert alert-danger">
                <?=$flashMessage?>
            </div>
        <?php endforeach; ?>
    </aside>
<?php endif; ?>

<?php if ($infoFlashes = $this->flashes('info')): ?>
    <aside id="info_flashes">
        <?php foreach ($infoFlashes as $flashMessage): ?>
            <div class="alert alert-info">
                <?=$flashMessage?>
            </div>
        <?php endforeach; ?>
    </aside>
<?php endif; ?>

<?php if ($successFlashes = $this->flashes('success')): ?>
    <aside id="success_flashes">
        <?php foreach ($successFlashes as $flashMessage): ?>
            <div class="alert alert-success">
                <?=$flashMessage?>
            </div>
        <?php endforeach; ?>
    </aside>
<?php endif; ?>

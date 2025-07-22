<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= isset($page_title) ? $page_title : 'Admin'; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <?php if (isset($crumb['link'])): ?>
                                <li class="breadcrumb-item">
                                    <a href="<?= $crumb['link']; ?>"><?= $crumb['title']; ?>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item active">
                                    <?= $crumb['title']; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
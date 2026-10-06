<?php
// path: /user/modules/example/views/admin/index.php
/* [AI:GPT-5.6 Sol | 2026-10-05 UTC] */

require APPROOT . '/views/inc/head.php';

$module = is_array($data['module'] ?? null) ? $data['module'] : [];
$state = (string) ($data['database_state'] ?? 'invalid');
$records = is_array($data['records'] ?? null) ? $data['records'] : [];
$message = $data['message'] ?? null;
$error = $data['error'] ?? null;
$moduleSlug = (string) ($module['module'] ?? 'example');
$updateUrl = trim((string) ($module['update_url'] ?? ''));
$hasUpdateSource = filter_var($updateUrl, FILTER_VALIDATE_URL) !== false
    && strtolower((string) parse_url($updateUrl, PHP_URL_SCHEME)) === 'https';
$canManageModule = (int) ($_SESSION['user_level'] ?? 0) >= 9;
?>

<p><small><a href="/admin">Admin</a> &gt;&gt; <strong>Example</strong></small></p>

<div class="container my-3">
    <h1>Example Module Administration</h1>

    <p>
        This administration interface is the working reference for the capabilities a ChAoS MVC user Module may need to implement.
        It demonstrates database state detection, SQL installation, exact schema updates, module-owned data deletion, explicit Data Reset,
        complete Create/Read/Update/Delete operations, validation, CSRF-protected POST actions, and visible success or failure status.
    </p>

    <?php if ($message): ?>
        <div class="alert alert-success" role="status"><strong>Success:</strong> <?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><strong>Error:</strong> <?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="records-tab" data-bs-toggle="tab" data-bs-target="#records-pane" type="button" role="tab" aria-controls="records-pane" aria-selected="true">Records</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="database-tab" data-bs-toggle="tab" data-bs-target="#database-pane" type="button" role="tab" aria-controls="database-pane" aria-selected="false">Database</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="data-tab" data-bs-toggle="tab" data-bs-target="#data-pane" type="button" role="tab" aria-controls="data-pane" aria-selected="false">Data</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="module-tab" data-bs-toggle="tab" data-bs-target="#module-pane" type="button" role="tab" aria-controls="module-pane" aria-selected="false">Module</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="records-pane" role="tabpanel" aria-labelledby="records-tab" tabindex="0">
            <?php if ($state !== 'current'): ?>
                <div class="alert alert-warning">Example record operations are unavailable until the database lifecycle is current. Use the <strong>Database</strong> tab to install or update the schema.</div>
            <?php else: ?>
                <section class="card border-secondary mb-4">
                    <div class="card-body">
                        <h2 class="h5">Create Example Record</h2>
                        <form method="POST" action="/admin/example">
                            <?= $this->csrf_field(); ?>
                            <input type="hidden" name="action" value="create">
                            <div class="mb-3"><label for="example-title" class="form-label">Title</label><input id="example-title" type="text" name="title" maxlength="150" class="form-control" required></div>
                            <div class="mb-3"><label for="example-body" class="form-label">Body</label><textarea id="example-body" name="body" rows="5" maxlength="2000" class="form-control" required></textarea></div>
                            <div class="form-check mb-3"><input id="example-active" class="form-check-input" type="checkbox" name="is_active" value="1" checked><label class="form-check-label" for="example-active">Active</label></div>
                            <button type="submit" class="btn btn-outline-primary">Create Record</button>
                        </form>
                    </div>
                </section>

                <section>
                    <h2 class="h5">Example Records</h2>
                    <?php if (empty($records)): ?><p class="text-secondary">No Example records exist.</p><?php endif; ?>
                    <?php foreach ($records as $record): ?>
                        <article class="card border-secondary mb-3"><div class="card-body">
                            <form method="POST" action="/admin/example">
                                <?= $this->csrf_field(); ?>
                                <input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= (int) $record['id']; ?>">
                                <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" maxlength="150" required class="form-control" value="<?= htmlspecialchars((string) $record['title'], ENT_QUOTES, 'UTF-8'); ?>"></div>
                                <div class="mb-3"><label class="form-label">Body</label><textarea name="body" rows="5" maxlength="2000" required class="form-control"><?= htmlspecialchars((string) $record['body'], ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" <?= (int) $record['is_active'] === 1 ? 'checked' : ''; ?>><label class="form-check-label">Active</label></div>
                                <button type="submit" class="btn btn-outline-success">Update Record</button>
                            </form>
                            <form method="POST" action="/admin/example" onsubmit="return confirm('Delete this Example record?');" class="mt-2">
                                <?= $this->csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $record['id']; ?>"><button type="submit" class="btn btn-outline-danger">Delete Record</button>
                            </form>
                        </div></article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="database-pane" role="tabpanel" aria-labelledby="database-tab" tabindex="0">
            <section class="card border-secondary mb-4"><div class="card-body">
                <h2 class="h5">Database Lifecycle</h2>
                <p class="text-secondary">Schema installation and migration are explicit operations. Loading an Admin or public page never installs or updates SQL.</p>

                <dl class="row mb-4">
                    <dt class="col-sm-3">Schema Version</dt><dd class="col-sm-9"><?= htmlspecialchars((string) ($module['schema_version'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt class="col-sm-3">Database State</dt><dd class="col-sm-9"><strong><?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?></strong></dd>
                </dl>

                <?php if ($state === 'missing'): ?>
                    <div class="alert alert-warning">The Example database schema is not installed.</div>
                    <form method="POST" action="/admin/example"><?= $this->csrf_field(); ?><input type="hidden" name="action" value="install_sql"><button type="submit" class="btn btn-primary">Install SQL</button></form>
                <?php elseif ($state === 'update'): ?>
                    <div class="alert alert-warning">The Example database schema requires a packaged migration.</div>
                    <form method="POST" action="/admin/example"><?= $this->csrf_field(); ?><input type="hidden" name="action" value="update_sql"><button type="submit" class="btn btn-primary">Update SQL</button></form>
                <?php elseif ($state === 'invalid'): ?>
                    <div class="alert alert-danger">The Example database state is invalid. No lifecycle or CRUD mutation will be performed until the state is corrected.</div>
                <?php else: ?>
                    <div class="alert alert-success">The Example schema is current.</div>
                <?php endif; ?>
            </div></section>
        </div>

        <div class="tab-pane fade" id="data-pane" role="tabpanel" aria-labelledby="data-tab" tabindex="0">
            <section class="card border-secondary mb-4"><div class="card-body">
                <h2 class="h5">Data Lifecycle</h2>
                <p class="text-secondary"><strong>Delete Data</strong> removes Example-owned records while preserving the installed schema. <strong>Data Reset</strong> removes mutable data and restores the packaged canonical reference records.</p>

                <?php if ($state === 'current'): ?>
                    <div class="d-flex gap-2 flex-wrap">
                        <form method="POST" action="/admin/example" onsubmit="return confirm('Delete all Example module data while preserving its schema?');"><?= $this->csrf_field(); ?><input type="hidden" name="action" value="delete_data"><button type="submit" class="btn btn-outline-danger">Delete Data</button></form>
                        <form method="POST" action="/admin/example" onsubmit="return confirm('Reset Example data to the canonical reference state?');"><?= $this->csrf_field(); ?><input type="hidden" name="action" value="reset_data"><button type="submit" class="btn btn-outline-warning">Data Reset</button></form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Data lifecycle operations require a current database schema.</div>
                <?php endif; ?>
            </div></section>
        </div>

        <div class="tab-pane fade" id="module-pane" role="tabpanel" aria-labelledby="module-tab" tabindex="0">
            <section class="card border-secondary mb-4"><div class="card-body">
                <h2 class="h5">Module Lifecycle</h2>
                <p class="text-secondary">Update, filesystem rollback, and Nuke are owned and enforced by ChAoS Core. The Example module only submits authenticated requests to those Core operations.</p>

                <dl class="row mb-4">
                    <dt class="col-sm-3">Module</dt><dd class="col-sm-9"><?= htmlspecialchars((string) ($module['name'] ?? 'Example'), ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt class="col-sm-3">Module Version</dt><dd class="col-sm-9"><span id="example-module-version"><?= htmlspecialchars((string) ($module['version'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span></dd>
                    <dt class="col-sm-3">Update Source</dt><dd class="col-sm-9"><code><?= htmlspecialchars($updateUrl, ENT_QUOTES, 'UTF-8'); ?></code></dd>
                </dl>

                <?php if (!$canManageModule): ?>
                    <div class="alert alert-warning">Core Module Lifecycle operations require a level 9 administrator.</div>
                <?php elseif ($hasUpdateSource): ?>
                    <button type="button" id="example-module-update" class="btn btn-secondary" data-module="<?= htmlspecialchars($moduleSlug, ENT_QUOTES, 'UTF-8'); ?>" data-action="check" disabled>Checking for updates...</button>
                    <p id="example-module-update-status" class="small mt-2 mb-0" role="status" aria-live="polite"></p>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-secondary" disabled>Local Module</button>
                    <p class="small text-secondary mt-2 mb-0">A valid HTTPS update_url is required in module.json.</p>
                <?php endif; ?>

                <?php if ($canManageModule): ?>
                    <hr>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" id="example-module-rollback" class="btn btn-outline-warning">Rollback Filesystem</button>
                        <form method="POST" action="/admin/uninstall" onsubmit="return confirm('Nuke Example? Core will remove its owned tables and module files.');">
                            <?= $this->csrf_field(); ?>
                            <input type="hidden" name="module" value="example">
                            <button type="submit" class="btn btn-danger">Nuke Module</button>
                        </form>
                    </div>
                    <p id="example-module-rollback-status" class="small mt-2 mb-0" role="status" aria-live="polite"></p>
                    <p class="small text-secondary mt-2 mb-0">Filesystem rollback restores the one retained previous module version. Database migrations are not reversed.</p>
                <?php endif; ?>
            </div></section>
        </div>
    </div>
</div>

<?php if ($canManageModule): ?>
<script>
const exampleModuleUpdateButton = document.getElementById('example-module-update');
const exampleModuleUpdateStatus = document.getElementById('example-module-update-status');
const exampleModuleVersion = document.getElementById('example-module-version');
const exampleModuleRollbackButton = document.getElementById('example-module-rollback');
const exampleModuleRollbackStatus = document.getElementById('example-module-rollback-status');
const exampleModuleUpdateCsrfToken = <?= json_encode($this->csrf_token()); ?>;

function exampleModuleRequestBody(module) {
    return new URLSearchParams({module, csrf_token: exampleModuleUpdateCsrfToken}).toString();
}

const exampleModuleSlug = <?= json_encode($moduleSlug); ?>;

function exampleModuleRollbackRequestBody(module) {
    return new URLSearchParams({module, csrf_token: exampleModuleUpdateCsrfToken, operation: 'rollback', confirm_files_only: '1'}).toString();
}

async function checkExampleModuleUpdate() {
    const btn = exampleModuleUpdateButton;
    if (!btn) return;
    btn.textContent = 'Checking for updates...';
    btn.className = 'btn btn-secondary';
    btn.dataset.action = 'check';
    btn.disabled = true;
    exampleModuleUpdateStatus.textContent = '';

    try {
        const response = await fetch('/admin/check_update', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: exampleModuleRequestBody(btn.dataset.module)
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.error || 'Update check failed.');

        if (result.update_available) {
            btn.textContent = `Update Available → ${result.available_version}`;
            btn.className = 'btn btn-success';
            btn.dataset.action = 'update';
            btn.disabled = false;
            exampleModuleUpdateStatus.textContent = `Installed: ${result.current_version}; available: ${result.available_version}.`;
        } else {
            btn.textContent = 'Up to Date';
            btn.className = 'btn btn-secondary';
            btn.dataset.action = 'current';
            btn.disabled = true;
            exampleModuleUpdateStatus.textContent = `Installed version ${result.current_version} is current.`;
        }
    } catch (error) {
        btn.textContent = 'Check failed — Retry';
        btn.className = 'btn btn-outline-secondary';
        btn.dataset.action = 'check';
        btn.disabled = false;
        exampleModuleUpdateStatus.textContent = error instanceof Error ? error.message : 'Update check failed.';
    }
}

async function installExampleModuleUpdate() {
    const btn = exampleModuleUpdateButton;
    if (!confirm('Install the verified Example module update through ChAoS Core?')) return;

    btn.textContent = 'Updating...';
    btn.disabled = true;
    exampleModuleUpdateStatus.textContent = 'ChAoS Core is installing the verified module update...';

    try {
        const response = await fetch('/admin/update', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: exampleModuleRequestBody(btn.dataset.module)
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.error || 'Module update failed.');

        btn.textContent = `Updated → ${result.version}`;
        btn.className = 'btn btn-secondary';
        btn.dataset.action = 'current';
        btn.disabled = true;
        exampleModuleVersion.textContent = result.version;
        exampleModuleUpdateStatus.textContent = result.message || `Example updated to ${result.version}.`;
    } catch (error) {
        btn.textContent = 'Update failed — Retry';
        btn.className = 'btn btn-outline-danger';
        btn.dataset.action = 'update';
        btn.disabled = false;
        exampleModuleUpdateStatus.textContent = error instanceof Error ? error.message : 'Module update failed.';
    }
}

if (exampleModuleUpdateButton) {
    exampleModuleUpdateButton.addEventListener('click', () => {
        if (exampleModuleUpdateButton.dataset.action === 'update') {
            installExampleModuleUpdate();
        } else if (exampleModuleUpdateButton.dataset.action === 'check') {
            checkExampleModuleUpdate();
        }
    });
}

exampleModuleRollbackButton.addEventListener('click', async () => {
    if (!confirm('Restore the previous Example filesystem version? Database changes will not be reversed.')) return;
    exampleModuleRollbackButton.disabled = true;
    exampleModuleRollbackStatus.textContent = 'ChAoS Core is restoring the previous module filesystem version...';
    try {
        const response = await fetch('/admin/update', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: exampleModuleRollbackRequestBody(exampleModuleSlug)
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.error || 'Module rollback failed.');
        exampleModuleVersion.textContent = result.version;
        exampleModuleRollbackStatus.textContent = result.message || `Example rolled back to ${result.version}.`;
        if (exampleModuleUpdateButton) await checkExampleModuleUpdate();
    } catch (error) {
        exampleModuleRollbackStatus.textContent = error instanceof Error ? error.message : 'Module rollback failed.';
    } finally {
        exampleModuleRollbackButton.disabled = false;
    }
});

if (exampleModuleUpdateButton) checkExampleModuleUpdate();
</script>
<?php endif; ?>

<?php
require APPROOT . '/views/inc/foot.php';
/* [End AI:GPT-5.6 Sol] */

<?php
// path: /user/modules/example/views/admin/index.php
/* [AI:GPT-5.6 Sol | 2026-10-05 UTC] */

require APPROOT . '/views/inc/head.php';

$module = is_array($data['module'] ?? null) ? $data['module'] : [];
$state = (string) ($data['database_state'] ?? 'invalid');
$records = is_array($data['records'] ?? null) ? $data['records'] : [];
$message = $data['message'] ?? null;
$error = $data['error'] ?? null;
?>

<p>
    <small>
        <a href="/admin">Admin</a> &gt;&gt;
        <strong>Example</strong>
    </small>
</p>

<div class="container my-3">
    <h1>Example Module Administration</h1>

    <p>
        This administration interface is the working reference for the
        capabilities a ChAoS MVC user Module may need to implement. It
        demonstrates database state detection, SQL installation, exact
        schema updates, module-owned data deletion, explicit Data Reset,
        complete Create/Read/Update/Delete operations, validation,
        CSRF-protected POST actions, and visible success or failure status.
    </p>

    <?php if ($message): ?>
        <div class="alert alert-success" role="status">
            <strong>Success:</strong>
            <?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Error:</strong>
            <?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button
                class="nav-link active"
                id="records-tab"
                data-bs-toggle="tab"
                data-bs-target="#records-pane"
                type="button"
                role="tab"
                aria-controls="records-pane"
                aria-selected="true"
            >Records</button>
        </li>
        <li class="nav-item" role="presentation">
            <button
                class="nav-link"
                id="lifecycle-tab"
                data-bs-toggle="tab"
                data-bs-target="#lifecycle-pane"
                type="button"
                role="tab"
                aria-controls="lifecycle-pane"
                aria-selected="false"
            >Lifecycle</button>
        </li>
    </ul>

    <div class="tab-content">
        <div
            class="tab-pane fade show active"
            id="records-pane"
            role="tabpanel"
            aria-labelledby="records-tab"
            tabindex="0"
        >
            <?php if ($state !== 'current'): ?>
                <div class="alert alert-warning">
                    Example data operations are unavailable until the database lifecycle is current.
                    Use the <strong>Lifecycle</strong> tab to install or update the schema.
                </div>
            <?php else: ?>
                <section class="card border-secondary mb-4">
                    <div class="card-body">
                        <h2 class="h5">Create Example Record</h2>

                        <form method="POST" action="/admin/example">
                            <?= $this->csrf_field(); ?>
                            <input type="hidden" name="action" value="create">

                            <div class="mb-3">
                                <label for="example-title" class="form-label">Title</label>
                                <input
                                    id="example-title"
                                    type="text"
                                    name="title"
                                    maxlength="150"
                                    class="form-control"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="example-body" class="form-label">Body</label>
                                <textarea
                                    id="example-body"
                                    name="body"
                                    rows="5"
                                    maxlength="2000"
                                    class="form-control"
                                    required
                                ></textarea>
                            </div>

                            <div class="form-check mb-3">
                                <input
                                    id="example-active"
                                    class="form-check-input"
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    checked
                                >
                                <label class="form-check-label" for="example-active">Active</label>
                            </div>

                            <button type="submit" class="btn btn-outline-primary">Create Record</button>
                        </form>
                    </div>
                </section>

                <section>
                    <h2 class="h5">Example Records</h2>

                    <?php if (empty($records)): ?>
                        <p class="text-secondary">No Example records exist.</p>
                    <?php endif; ?>

                    <?php foreach ($records as $record): ?>
                        <article class="card border-secondary mb-3">
                            <div class="card-body">
                                <form method="POST" action="/admin/example">
                                    <?= $this->csrf_field(); ?>
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= (int) $record['id']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input
                                            type="text"
                                            name="title"
                                            maxlength="150"
                                            required
                                            class="form-control"
                                            value="<?= htmlspecialchars((string) $record['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Body</label>
                                        <textarea
                                            name="body"
                                            rows="5"
                                            maxlength="2000"
                                            required
                                            class="form-control"
                                        ><?= htmlspecialchars((string) $record['body'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_active"
                                            value="1"
                                            <?= (int) $record['is_active'] === 1 ? 'checked' : ''; ?>
                                        >
                                        <label class="form-check-label">Active</label>
                                    </div>

                                    <button type="submit" class="btn btn-outline-success">Update Record</button>
                                </form>

                                <form
                                    method="POST"
                                    action="/admin/example"
                                    onsubmit="return confirm('Delete this Example record?');"
                                    class="mt-2"
                                >
                                    <?= $this->csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $record['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger">Delete Record</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </div>

        <div
            class="tab-pane fade"
            id="lifecycle-pane"
            role="tabpanel"
            aria-labelledby="lifecycle-tab"
            tabindex="0"
        >
            <section class="card border-secondary mb-4">
                <div class="card-body">
                    <h2 class="h5">Module &amp; Data Lifecycle</h2>

                    <p class="text-secondary">
                        Data lifecycle operations are distinct from the Module lifecycle.
                        <strong>Delete Data</strong> removes Example-owned records while preserving the installed schema.
                        <strong>Data Reset</strong> removes mutable Example data and restores the canonical reference records supplied with the Module.
                    </p>

                    <dl class="row mb-4">
                        <dt class="col-sm-3">Module</dt>
                        <dd class="col-sm-9"><?= htmlspecialchars((string) ($module['name'] ?? 'Example'), ENT_QUOTES, 'UTF-8'); ?></dd>

                        <dt class="col-sm-3">Module Version</dt>
                        <dd class="col-sm-9"><?= htmlspecialchars((string) ($module['version'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>

                        <dt class="col-sm-3">Schema Version</dt>
                        <dd class="col-sm-9"><?= htmlspecialchars((string) ($module['schema_version'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>

                        <dt class="col-sm-3">Database State</dt>
                        <dd class="col-sm-9"><strong><?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?></strong></dd>
                    </dl>

                    <?php if ($state === 'missing'): ?>
                        <div class="alert alert-warning">The Example database schema is not installed.</div>
                        <form method="POST" action="/admin/example">
                            <?= $this->csrf_field(); ?>
                            <input type="hidden" name="action" value="install_sql">
                            <button type="submit" class="btn btn-primary">Install SQL</button>
                        </form>
                    <?php elseif ($state === 'update'): ?>
                        <div class="alert alert-warning">The Example database schema requires a packaged migration.</div>
                        <form method="POST" action="/admin/example">
                            <?= $this->csrf_field(); ?>
                            <input type="hidden" name="action" value="update_sql">
                            <button type="submit" class="btn btn-primary">Update SQL</button>
                        </form>
                    <?php elseif ($state === 'invalid'): ?>
                        <div class="alert alert-danger">
                            The Example database state is invalid. No lifecycle or CRUD mutation will be performed until the state is corrected.
                        </div>
                    <?php elseif ($state === 'current'): ?>
                        <div class="d-flex gap-2 flex-wrap">
                            <form
                                method="POST"
                                action="/admin/example"
                                onsubmit="return confirm('Delete all Example module data while preserving its schema?');"
                            >
                                <?= $this->csrf_field(); ?>
                                <input type="hidden" name="action" value="delete_data">
                                <button type="submit" class="btn btn-outline-danger">Delete Data</button>
                            </form>

                            <form
                                method="POST"
                                action="/admin/example"
                                onsubmit="return confirm('Reset Example data to the canonical reference state?');"
                            >
                                <?= $this->csrf_field(); ?>
                                <input type="hidden" name="action" value="reset_data">
                                <button type="submit" class="btn btn-outline-warning">Data Reset</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php
require APPROOT . '/views/inc/foot.php';
/* [End AI:GPT-5.6 Sol] */

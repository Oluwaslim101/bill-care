<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($alert['title']) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Message</label>
    <textarea name="message" class="form-control" rows="3" required><?= htmlspecialchars($alert['message']) ?></textarea>
</div>
<div class="mb-3">
    <label class="form-label">Alert Type</label>
    <select name="alert_type" class="form-select" required>
        <option value="">-- Select Type --</option>
        <option value="info" <?= $alert['alert_type'] === 'info' ? 'selected' : '' ?>>Info</option>
        <option value="warning" <?= $alert['alert_type'] === 'warning' ? 'selected' : '' ?>>Warning</option>
        <option value="success" <?= $alert['alert_type'] === 'success' ? 'selected' : '' ?>>Success</option>
        <option value="danger" <?= $alert['alert_type'] === 'danger' ? 'selected' : '' ?>>Danger</option>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Display Start</label>
    <input type="datetime-local" name="display_start" class="form-control" value="<?= htmlspecialchars($alert['display_start']) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Display End</label>
    <input type="datetime-local" name="display_end" class="form-control" value="<?= htmlspecialchars($alert['display_end']) ?>" required>
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= $alert['is_active'] ? 'checked' : '' ?>>
    <label class="form-check-label">Active</label>
</div>

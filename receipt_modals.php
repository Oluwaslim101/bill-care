<!-- Info Modal -->
<div class="modal fade dialogbox" id="DialogBasic" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Receipt Info</h5></div>
            <div class="modal-body">You can download or screenshot this receipt for your records.</div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dispute Modal -->
<div class="modal fade" id="disputeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="disputeForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Dispute Transaction</h5>
      </div>
      <div class="modal-body">
        <p>You're disputing transaction ref: <strong><?= $transaction_ref; ?></strong></p>
        <div class="form-group">
          <label for="disputeReason">Reason</label>
          <textarea class="form-control" name="reason" id="disputeReason" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="reference" value="<?= $transaction_ref; ?>">
        <input type="hidden" name="session_id" value="<?= $session_id; ?>">
        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Submit Dispute</button>
      </div>
    </form>
  </div>
</div>

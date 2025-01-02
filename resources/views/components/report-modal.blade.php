<!-- The Report Post Modal -->
<div class="modal fade" id="reportPostModal" tabindex="-1" aria-labelledby="reportPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportPostModalLabel">Report Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportPostForm" action="{{ route('forum.report.submit') }}" method="POST">
                    @csrf
                    <!-- Hidden input to store forum_id -->
                    <input type="hidden" name="forum_id" id="report_forum_id" value="">

                    <!-- Reason for Reporting -->
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Explain why you're reporting this post" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setForumId(forumId) {
        // Set the forum_id in the hidden input of the report form
        document.getElementById('report_forum_id').value = forumId;
    }
</script>
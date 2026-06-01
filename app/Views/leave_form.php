<h3>Submit Leave</h3>

<form method="post" action="<?= base_url('leave/save') ?>">
    <select name="leave_type" onchange="toggle()" required>
        <option value="">Select</option>
        <option value="Sick Leave">Sick Leave</option>
        <option value="Casual Leave">Casual Leave</option>
        <option value="Other">Other</option>
    </select>

    <input type="text" name="other_reason" id="other" style="display:none">

    <button type="submit">Submit</button>
</form>

<script>
function toggle(){
    document.getElementById('other').style.display =
        event.target.value === 'Other' ? 'block' : 'none';
}
</script>

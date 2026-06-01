<h2>Add Attendance</h2>
<form method="post" action="/attendance/save">
    <label>Date:</label>
    <input type="date" name="date" required><br><br>

    <label>Status:</label>
    <select name="status" required>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
    </select><br><br>

    <button type="submit">Submit</button>
</form>

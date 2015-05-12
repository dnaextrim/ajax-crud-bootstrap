<h2>
	User
	<input type="button" id="btn_add" class="pull-right" value="ADD" />
</h2>
<hr />

<form id="form_data" method="POST" action="crud.php">
	<label>Username</label>
	<input type="text" name="username" id="username" required />

	<label>Password</label>
	<input type="password" name="password" id="password" required />

	<label>Fullname</label>
	<input type="text" name="fullname" id="fullname" />

	<label>Email</label>
	<input type="email" name="email" id="email" required />

	<label>Level</label>
	<select name="level" id="level" required>
		<option value="admin">Admin</option>
		<option value="user">User</option>
	</select>


	<input type="hidden" id="username_old" name="username_old" value="" />

	<input type="submit" id="btn_save" value="SAVE" /> &nbsp;
	<input type="reset" id="btn_cancel" value="CANCEL" />
</form>


<table id="list">
	<thead>
		<tr>
			<th>Username</th>
			<th>Fullname</th>
			<th>Email</th>
			<th>Level</th>
		</tr>
	</thead>
	
	<tbody>
		
	</tbody>
</table>
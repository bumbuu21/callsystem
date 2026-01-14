<ul>
    <li><a href="#">Dashboard</a></li>
    <li><a href="#">Calls</a></li>

    @role('manager')
        <li><a href="#">Reports</a></li>
    @endrole

    @role('admin')
        <li><a href="#">Users</a></li>
        <li><a href="#">Settings</a></li>
    @endrole
</ul>

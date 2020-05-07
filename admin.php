<?php
require_once __DIR__ . '/inc/bootstrap.php';
//requireAdmin();
require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/nav.php';
?>
<div class="container">
    <div class="well">
        <h2>Admin</h2>
        <?php print display_errors(); ?>
        <?php print display_success(); ?>
          <div class="panel">
              <h4>Users</h4>
              <table class="table table-striped">
                  <thead>
                  <tr>
                      <th>Email</th>
                      <th>Registered</th>
                      <th>Promote/Demote</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php foreach(getAllUsers() as $user) : ?>
                      <tr>
                        <td><?php echo $user["username"]; ?></td>
                        <td><?php echo $user["created_at"]; ?></td>
                        <td>
                          <?php if($user["role_id"] == 1): ?>                            
                          <a href="/procedures/adjustRole.php?roleId=2&userId=<?php echo $user["id"]; ?>" 
                            class="btn btn-xs btn-warning">Demote to General User</a>            
                        <?php elseif($user["role_id"] == 2): ?>
                        <a href="/procedures/adjustRole.php?roleId=1&userId=<?php echo $user["id"]; ?>" 
                          class="btn btn-xs btn-success">Promote to Admin</a>
                        <?php endif; ?></td>                    
                    </tr>  
                    <?php endforeach; ?>
                 </tbody>
              </table>
          </div>
    </div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';

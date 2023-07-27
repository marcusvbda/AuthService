<?php

Broadcast::channel('Alert.User.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});

Broadcast::channel('Alert.Tenant.{id}', function ($user, $id) {
	return (int) @$user->tenant_id === (int) $id;
});

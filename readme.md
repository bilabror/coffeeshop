# Coffe Store versi Beta

> Aplikasi ini masih dalam tahap devlopment


# Tools dan Instalasi

<details close="close">
<summary> PC / Laptop </summary>


### Tools
Software untuk menjalankan dan mempermudah mengedit keperluan Website

| Software | Link |
|--------|--------|
| **XAMPP** | [Download disini](https://www.apachefriends.org/download.html) |
| **VSCODE** | [Download disini](https://code.visualstudio.com) |

### Instalasi

1. [Clone](https://github.com/Alhiqny404/coffeshop) atau [Download](https://github.com/Alhiqny404/coffeshop/archive/refs/heads/main.zip) file ini lalu simpan dilocalhost.
2. Buat database lalu import file [coffe_store.sql](./db/coffe_store.sql)
3. Configurasikan database [database.sql](./application/config/database.php)
```
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'nama_database',
```

> ! base_url telah dibuat dinamis !, namun jika terjadi kesalahan, silahkan ubah pada file [config.sql](./application/config/config.php)
```
$config['base_url'] = 'http://localhost/nama_folder';
```
</details>


<details close="close">
  <summary> Android </summary>


### Tools
Aplikasi untuk menjalankan dan mempermudah mengedit keperluan Website

| Software | Link |
|--------|--------|
| **KSWEB** | [Download disini](https://play.google.com/store/apps/details?id=ru.kslabs.ksweb) |
| **ACODE** | [Download disini](https://play.google.com/store/apps/details?id=com.foxdebug.acodefree) |

</details>

## Akun Demo
#### Admin
```
email : admin@example.com
password : admin
```
#### Customer
```
email : user@example.com
password : 123
```

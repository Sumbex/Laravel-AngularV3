import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  usuario: string;
  nombreUsuario;
  isLogged: boolean;
  rol;

  constructor(private _userService: UsuarioService, private router: Router) { }

  ngOnInit() {
    this.usuario = this._userService.getUsuario();
    if (this.usuario != null) {
      this.isLogged = true
    } else {
      this.isLogged = false;
    }

    this.getNombre();
    //obtener rol para habilitar boton cambio de portal
    this.rol = localStorage.getItem('rol');
  }

  logOut() {
    this._userService.logout().subscribe(
      res => {
        localStorage.clear();
        this.router.navigate(['']);
      }
    );
  }


  getNombre() {
    this._userService.getUsuarioLogeado().subscribe(
      response => {
        this.nombreUsuario = response.name + " " + response.a_paterno;
      },
      error => {
        console.log(error);
      }
    );
  }
}

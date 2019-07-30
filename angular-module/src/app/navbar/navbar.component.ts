import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  usuario: string;
  nombreUsuario;
  isLogged: boolean;

  constructor(private _userService: UsuarioService) { }

  ngOnInit() {
    this.usuario = this._userService.getUsuario();
    if(this.usuario != null){
      this.isLogged = true
    }else{
      this.isLogged = false;
    }

    this.getNombre();
  }

  logOut(){
    this._userService.logOut();
  }

  getNombre(){
    this._userService.getUsuarioLogeado().subscribe(
      response => {
        this.nombreUsuario = response.name + " " + response.a_paterno;
        console.log(this.nombreUsuario);
      },
      error => {
        console.log(error);
      }
    );
  }
}

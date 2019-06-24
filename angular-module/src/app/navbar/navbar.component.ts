import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  usuario: string;
  isLogged: boolean;

  constructor(private _userService: UsuarioService) { }

  ngOnInit() {
    this.usuario = this._userService.getUsuario();
    if(this.usuario != null){
      this.isLogged = true
    }else{
      this.isLogged = false;
    }
  }

  logOut(){
    this._userService.logOut();
  }

}

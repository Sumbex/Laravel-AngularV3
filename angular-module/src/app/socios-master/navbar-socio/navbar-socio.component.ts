import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-navbar-socio',
  templateUrl: './navbar-socio.component.html',
  styleUrls: ['./navbar-socio.component.css']
})
export class NavbarSocioComponent implements OnInit {

  constructor(private _userService: UsuarioService) { }

  ngOnInit() {
  }

  logOut(){
    this._userService.logOut();
  }

}

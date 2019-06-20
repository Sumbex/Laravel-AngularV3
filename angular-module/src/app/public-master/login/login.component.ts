import { Component, OnInit } from '@angular/core';
import { Usuario } from '../../modelos/usuarios.model';
import { UsuarioService } from '../../servicios/usuarios.service';
import { Router } from '@angular/router';
 
@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public usuario: Usuario;
  public status: string;

  constructor(private _userService: UsuarioService, private router: Router) {
    this.usuario = new Usuario();
  }

  ngOnInit() {
  }

  onSubmit(form){
    this._userService.login(this.usuario).subscribe(
      response => {
        console.log(response);
        this.router.navigate(['AuthMaster']);
      },
      error => {
        this.status = error;
        console.log(<any>error);
      }
    );
    console.log(this.usuario);
  }

}

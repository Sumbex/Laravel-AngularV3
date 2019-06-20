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
  public token: string;

  constructor(private _userService: UsuarioService, private router: Router) {
    this.usuario = new Usuario();
  }

  ngOnInit() {
  }

  onSubmit(form){
    this._userService.login(this.usuario, true).subscribe(
      response => {
        this.token = response.token;
        localStorage.setItem('token', JSON.stringify(this.token));
        localStorage.setItem('usuario', JSON.stringify(this.usuario.email))
        this.router.navigate(['AuthMaster']);
      },
      error => {
        this.status = error;
        this.router.navigate(['**']);
      }
    );
  }

}

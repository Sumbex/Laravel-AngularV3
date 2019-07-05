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
  public loading: boolean = false;

  public lockLogin: boolean = false;

  constructor(private _userService: UsuarioService, private router: Router) {
    this.usuario = new Usuario();
  }

  ngOnInit() {
    this.token = localStorage.getItem('token');
    if (this.token != null) {
      this.router.navigate(['AuthMaster']);
    }
  }

  onSubmit(form) {
    if(this.lockLogin == false){
    this.lockLogin = true;
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
        this.lockLogin = false;
        console.log(this.status);
      }
    );
    this.loading = true;
  }
  }

}

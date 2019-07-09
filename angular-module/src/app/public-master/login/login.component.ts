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
  public noEncontrado = false;

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
        if(response.status == 'success'){
        this.token = response.token;
        localStorage.setItem('token', JSON.stringify(this.token));
        localStorage.setItem('usuario', JSON.stringify(this.usuario.email));
        console.log(response);
        this.lockLogin = false;
        this.router.navigate(['AuthMaster']);
      }else{
        console.log("Revise que su usuario sea correcto");
        this.lockLogin = false;
        this.noEncontrado = true;
        this.loading = false;
      }
      },
      error => {
        this.status = error;
        console.log("Revise que su usuario sea correcto");
        this.lockLogin = false;
        this.noEncontrado = true;
        this.loading = false;
      }
    );
    this.noEncontrado = false;
    this.loading = true;
  }
  }

}

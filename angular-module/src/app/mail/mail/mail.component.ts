import { Component, OnInit } from '@angular/core';
import { MailService } from 'src/app/servicios/mail.service';

@Component({
  selector: 'app-mail',
  templateUrl: './mail.component.html',
  styleUrls: ['./mail.component.css']
})
export class MailComponent implements OnInit {

  constructor(private _mail:MailService) { }
  email:string="alejandro.e.g.t29@gmail.com";
  ngOnInit() {
  }

  enviar_correo(){
    this._mail.enviar_correo(this.email).subscribe(res=>{
      alert(res.estado)
    });
  }

}

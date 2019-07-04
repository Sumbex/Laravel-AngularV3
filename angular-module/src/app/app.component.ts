import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { TipoCuentasService } from './servicios/tipo-cuentas.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class AppComponent implements OnInit {
  title = 'angular-module';

  constructor(){

  }

  ngOnInit(){  }

}

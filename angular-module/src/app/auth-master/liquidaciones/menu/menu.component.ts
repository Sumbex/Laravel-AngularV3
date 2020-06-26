import { Component, OnInit, ViewChild } from '@angular/core';
import { LiquidacionComponent } from '../liquidacion/liquidacion.component';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})

export class MenuComponent implements OnInit {

  @ViewChild(LiquidacionComponent, {static: false}) hijo: LiquidacionComponent;

  testVariable = '1';

  constructor() { }

  ngOnInit() {
  }

  test(){
    this.hijo.empleado = '';
    console.log('Hola mundo');
  }

}

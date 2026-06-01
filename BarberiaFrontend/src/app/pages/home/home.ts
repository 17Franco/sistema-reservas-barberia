import { Component } from '@angular/core';
import { RouterOutlet, RouterLink } from '@angular/router';
import { NavBar } from "../../components/nav-bar/nav-bar";

@Component({
  selector: 'app-home',
  imports: [RouterOutlet, RouterLink, NavBar],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {
  
}
